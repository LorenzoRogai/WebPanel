using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Net;
using System.ServiceModel;
using Web.Panel;

namespace Test
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        WebPanel MyWebPanel;
        public static int runningtime_seconds = 0;
        public static int MyCounter = 0;
        public static string MyText;
        public static List<User> Users;

        private void Form1_Load(object sender, EventArgs e)
        {
            Users = new List<User>();
            Users.Add(new User("Jon", 30, "United States"));
            Users.Add(new User("Mario", 20, "Italy"));
            foreach (User user in Form1.Users)
            {
                listBox1.Items.Add(user.Name);
            }
            //Start WebPanel on ip:port and authentication username password
            MyWebPanel = new WebPanel(IPAddress.Parse("127.0.0.1"), 8000, "admin", "password");
            //Add some event handler
            LoginValidator.OnLoginAttempt += new LoginValidator.onLoginAttemptHandler(onLoginAttempt);
            WebPanel.AfterReceiveRequest += new WebPanel.AfterReceiveRequestHandler(AfterReceiveRequest);
            WebPanel.OnException += new WebPanel.OnExceptionHandler(OnException);
            //Start webService
            MyWebPanel.Start(typeof(WebPanelHandler), typeof(IWebPanel));
        }

        void onLoginAttempt(object sender, bool response)
        {
            richTextBox1.BeginInvoke(new MethodInvoker(delegate() { richTextBox1.AppendText("Login response: " + response + Environment.NewLine); }));
        }

        void AfterReceiveRequest(object sender, ref System.ServiceModel.Channels.Message request, IClientChannel channel, InstanceContext instanceContext)
        {
            var action = OperationContext.Current.IncomingMessageHeaders.Action;
            var operationName = action.Substring(action.LastIndexOf("/") + 1);
            richTextBox1.BeginInvoke(new MethodInvoker(delegate() { richTextBox1.AppendText(operationName + " invoked" + Environment.NewLine); }));
        }

        void OnException(object sender, Exception e)
        {
            MessageBox.Show(e.Message);
        }

        private void button1_Click(object sender, EventArgs e)
        {
            MyCounter++;
            label2.Text = "Value: " + MyCounter;
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            runningtime_seconds++;
            label1.Text = "Running time: " + runningtime_seconds + " seconds";
            richTextBox1.ScrollToCaret();
        }

        private void textBox1_TextChanged(object sender, EventArgs e)
        {
            MyText = textBox1.Text;
        }

        private void listBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            foreach (User user in Form1.Users)
            {
                if (user.Name == listBox1.SelectedItem.ToString())
                {
                    label3.Text = "Name: " + user.Name + "\nAge: " + user.Age + "\nCountry:\n" + user.Country;
                }
            }
        }
    }

    public class User
    {
        public string Name;
        public int Age;
        public string Country;

        public User(string Name, int Age, string Country)
        {
            this.Name = Name;
            this.Age = Age;
            this.Country = Country;
        }
    }

    public class WebPanelHandler : IWebPanel
    {
        public int RunningTime()
        {
            return Form1.runningtime_seconds;
        }

        public int MyCounter()
        {
            return Form1.MyCounter;
        }

        public string MyText()
        {
            return Form1.MyText;
        }

        public void CloseApplication()
        {
            if (MessageBox.Show("Do you really want to close application?", "Confirm", MessageBoxButtons.YesNo) == DialogResult.Yes)
            {
                Application.Exit();
            }
        }

        public string GetUserInfo(string username, string age)
        {
            if (username == "" && age == "")
                return null;

            foreach (User user in Form1.Users)
            {
                int uage = 0;
                int.TryParse(age, out uage);
                if (user.Name.ToLower() == username.ToLower() || user.Age == uage)
                {
                    return "Name: " + user.Name + "<br>Age: " + user.Age + "<br>Country: " + user.Country;
                }
            }
            return null;
        }
    }

    [ServiceContract]
    public interface IWebPanel
    {
        [OperationContract]
        int RunningTime();
        [OperationContract]
        int MyCounter();
        [OperationContract]
        void CloseApplication();
        [OperationContract]
        string MyText();
        [OperationContract]
        string GetUserInfo(string username, string age);
    }
}
