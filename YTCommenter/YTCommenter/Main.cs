using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using YTCommenter.Dal;

namespace YTCommenter
{
    public partial class Main : Form
    {
        public Main()
        {
            InitializeComponent();

            var db = new MySqlContext();
        }
    }
}
