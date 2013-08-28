using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;
using System.ServiceModel;
using System.ServiceModel.Description;
using System.ServiceModel.Security;
using System.ServiceModel.Dispatcher;
using System.ServiceModel.Channels;
using System.IdentityModel.Selectors;
using System.IdentityModel.Tokens;
using System.Reflection;

namespace Web.Panel
{
    public class LoginValidator : UserNamePasswordValidator
    {
        public delegate void onLoginAttemptHandler(object sender, bool response);
        public static event onLoginAttemptHandler OnLoginAttempt;
        /// <summary>
        /// Handle WebPanel Login Event</summary>      
        /// <param name="userName">Username</param>   
        /// <param name="password">Password</param>   
        /// </summary>

        [System.Diagnostics.DebuggerHidden]
        public override void Validate(string userName, string password)
        {
            if ((userName != WebPanel.Username) || (password != WebPanel.Password))
            {
                if (OnLoginAttempt != null)
                    OnLoginAttempt(this, false);

                throw new SecurityTokenException();
            }
            else
            {
                if (OnLoginAttempt != null)
                    OnLoginAttempt(this, true);
            }
        }
    }

    public class WebPanel
    {
        private IPAddress IP;
        private int Port;
        public static string Username;
        public static string Password;
        public delegate void AfterReceiveRequestHandler(object sender, ref Message request, IClientChannel channel, InstanceContext instanceContext);
        public static event AfterReceiveRequestHandler AfterReceiveRequest;
        public delegate void BeforeSendReplyHandler(object sender, ref Message reply, object correlationState);
        public static event BeforeSendReplyHandler BeforeSendReply;
        public delegate void OnExceptionHandler(object sender, Exception e);
        public static event OnExceptionHandler OnException;
        /// <summary>
        /// initialize webService</summary>      
        /// <param name="Ip">Ip to bind</param>   
        /// <param name="port">Port to bind</param>   
        /// <param name="username">Set username</param>   
        /// <param name="password">Set password</param>      
        /// </summary>
        public WebPanel(IPAddress IP, int Port, string username, string password)
        {
            this.IP = IP;
            this.Port = Port;
            Username = username;
            Password = password;
        }

        /// <summary>
        /// Custom webService Behavior to log incoming/outgoing Method call/response
        /// </summary>     
        class webServiceEvent : IEndpointBehavior, IDispatchMessageInspector
        {
            public void AddBindingParameters(ServiceEndpoint endpoint, BindingParameterCollection bindingParameters)
            {
            }

            public void ApplyClientBehavior(ServiceEndpoint endpoint, ClientRuntime clientRuntime)
            {
            }

            public void ApplyDispatchBehavior(ServiceEndpoint endpoint, EndpointDispatcher endpointDispatcher)
            {
                endpointDispatcher.DispatchRuntime.MessageInspectors.Add(this);
            }

            public void Validate(ServiceEndpoint endpoint)
            {
            }

            public object AfterReceiveRequest(ref Message request, IClientChannel channel, InstanceContext instanceContext)
            {
                if (WebPanel.AfterReceiveRequest != null)
                    WebPanel.AfterReceiveRequest(this, ref request, channel, instanceContext);

                return null;
            }

            public void BeforeSendReply(ref Message reply, object correlationState)
            {
                if (WebPanel.BeforeSendReply != null)
                    WebPanel.BeforeSendReply(this, ref reply, correlationState);
            }
        }

        /// <summary>
        /// Start WebPanel    
        /// </summary>     
        public bool Start(Type serviceType, Type implementedContract)
        {
            if (OnException == null)
            {
                throw new Exception("Assign a exception handler");
            }

            Uri baseAddress = new Uri("http://" + IP.ToString() + ":" + Port + "/WebPanel/");

            // Create the ServiceHost. Bind on http://ip:port/WebPanel/
            ServiceHost selfHost = new ServiceHost(serviceType, baseAddress);

            //Binding to configure endpoint
            BasicHttpBinding http = new BasicHttpBinding();

            //Set a basic username/password authentication
            http.Security.Mode = BasicHttpSecurityMode.TransportCredentialOnly;

            http.Security.Transport.ClientCredentialType = HttpClientCredentialType.Basic;

            try
            {
                //Add the endpoint to the service host
                ServiceEndpoint endpoint = selfHost.AddServiceEndpoint(implementedContract, http, "RemoteControlService");
                //Add the Custom webService Behavior to endpoint
                endpoint.Behaviors.Add(new webServiceEvent());

                //Set the custom username/password validation
                selfHost.Credentials.UserNameAuthentication.UserNamePasswordValidationMode = UserNamePasswordValidationMode.Custom;
                selfHost.Credentials.UserNameAuthentication.CustomUserNamePasswordValidator = new LoginValidator();

                // Enable metadata publishing.
                ServiceMetadataBehavior smb = selfHost.Description.Behaviors.Find<ServiceMetadataBehavior>();
                if (smb == null)
                {
                    smb = new ServiceMetadataBehavior();
                    smb.HttpGetEnabled = true;
                    selfHost.Description.Behaviors.Add(smb);
                }

                try
                {
                    //Start WebPanel
                    selfHost.Open();
                    return true;
                }
                catch (Exception e)
                {
                    if (e is AddressAccessDeniedException)
                    {
                        if (OnException != null)
                            OnException(this, e);
                        return false;
                    }

                    if (e is AddressAlreadyInUseException)
                    {
                        if (OnException != null)
                            OnException(this, e);
                        return false;
                    }
                }
            }
            catch (CommunicationException ce)
            {
                if (OnException != null)
                    OnException(this, ce);
                selfHost.Abort();
                return false;
            }
            return false;
        }
    }
}
