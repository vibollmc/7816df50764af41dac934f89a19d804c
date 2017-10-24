namespace SshChecker
{
    partial class FrmSshChecker
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(FrmSshChecker));
            this.btnBrowseFile = new System.Windows.Forms.Button();
            this.btnRun = new System.Windows.Forms.Button();
            this.cboNumberTheards = new System.Windows.Forms.ComboBox();
            this.label1 = new System.Windows.Forms.Label();
            this.prbRunningStatus = new System.Windows.Forms.ProgressBar();
            this.label2 = new System.Windows.Forms.Label();
            this.lblRunningStatus = new System.Windows.Forms.Label();
            this.btnSaves = new System.Windows.Forms.Button();
            this.lblSshLoaded = new System.Windows.Forms.Label();
            this.lblCurrentRunning = new System.Windows.Forms.Label();
            this.btnClear = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // btnBrowseFile
            // 
            this.btnBrowseFile.Location = new System.Drawing.Point(232, 21);
            this.btnBrowseFile.Name = "btnBrowseFile";
            this.btnBrowseFile.Size = new System.Drawing.Size(121, 23);
            this.btnBrowseFile.TabIndex = 2;
            this.btnBrowseFile.Text = "Select SSH File";
            this.btnBrowseFile.UseVisualStyleBackColor = true;
            this.btnBrowseFile.Click += new System.EventHandler(this.button1_Click);
            // 
            // btnRun
            // 
            this.btnRun.Location = new System.Drawing.Point(107, 82);
            this.btnRun.Name = "btnRun";
            this.btnRun.Size = new System.Drawing.Size(91, 23);
            this.btnRun.TabIndex = 5;
            this.btnRun.Text = "Run Check";
            this.btnRun.UseVisualStyleBackColor = true;
            this.btnRun.Click += new System.EventHandler(this.button2_Click);
            // 
            // cboNumberTheards
            // 
            this.cboNumberTheards.FormattingEnabled = true;
            this.cboNumberTheards.Items.AddRange(new object[] {
            "1",
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "8",
            "9",
            "10",
            "11",
            "12",
            "13",
            "14",
            "15",
            "16",
            "17",
            "18",
            "19",
            "20"});
            this.cboNumberTheards.Location = new System.Drawing.Point(107, 52);
            this.cboNumberTheards.Name = "cboNumberTheards";
            this.cboNumberTheards.Size = new System.Drawing.Size(62, 21);
            this.cboNumberTheards.TabIndex = 4;
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(12, 55);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(89, 13);
            this.label1.TabIndex = 3;
            this.label1.Text = "Number Threads:";
            // 
            // prbRunningStatus
            // 
            this.prbRunningStatus.Dock = System.Windows.Forms.DockStyle.Bottom;
            this.prbRunningStatus.Location = new System.Drawing.Point(0, 145);
            this.prbRunningStatus.Name = "prbRunningStatus";
            this.prbRunningStatus.Size = new System.Drawing.Size(379, 15);
            this.prbRunningStatus.Step = 1;
            this.prbRunningStatus.Style = System.Windows.Forms.ProgressBarStyle.Continuous;
            this.prbRunningStatus.TabIndex = 8;
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(23, 25);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(78, 13);
            this.label2.TabIndex = 0;
            this.label2.Text = "SSH File Input:";
            // 
            // lblRunningStatus
            // 
            this.lblRunningStatus.BackColor = System.Drawing.Color.Transparent;
            this.lblRunningStatus.Dock = System.Windows.Forms.DockStyle.Bottom;
            this.lblRunningStatus.Location = new System.Drawing.Point(0, 127);
            this.lblRunningStatus.Name = "lblRunningStatus";
            this.lblRunningStatus.Size = new System.Drawing.Size(379, 18);
            this.lblRunningStatus.TabIndex = 7;
            this.lblRunningStatus.Text = "label3";
            this.lblRunningStatus.TextAlign = System.Drawing.ContentAlignment.MiddleLeft;
            // 
            // btnSaves
            // 
            this.btnSaves.Location = new System.Drawing.Point(232, 82);
            this.btnSaves.Name = "btnSaves";
            this.btnSaves.Size = new System.Drawing.Size(121, 23);
            this.btnSaves.TabIndex = 6;
            this.btnSaves.Text = "Save SSH Fresh";
            this.btnSaves.UseVisualStyleBackColor = true;
            this.btnSaves.Click += new System.EventHandler(this.btnSaves_Click);
            // 
            // lblSshLoaded
            // 
            this.lblSshLoaded.AutoSize = true;
            this.lblSshLoaded.Location = new System.Drawing.Point(108, 25);
            this.lblSshLoaded.Name = "lblSshLoaded";
            this.lblSshLoaded.Size = new System.Drawing.Size(35, 13);
            this.lblSshLoaded.TabIndex = 1;
            this.lblSshLoaded.Text = "label3";
            // 
            // lblCurrentRunning
            // 
            this.lblCurrentRunning.Dock = System.Windows.Forms.DockStyle.Bottom;
            this.lblCurrentRunning.Location = new System.Drawing.Point(0, 114);
            this.lblCurrentRunning.Name = "lblCurrentRunning";
            this.lblCurrentRunning.Size = new System.Drawing.Size(379, 13);
            this.lblCurrentRunning.TabIndex = 9;
            this.lblCurrentRunning.Text = "label3";
            // 
            // btnClear
            // 
            this.btnClear.Location = new System.Drawing.Point(232, 50);
            this.btnClear.Name = "btnClear";
            this.btnClear.Size = new System.Drawing.Size(121, 23);
            this.btnClear.TabIndex = 10;
            this.btnClear.Text = "Clear Queue";
            this.btnClear.UseVisualStyleBackColor = true;
            this.btnClear.Click += new System.EventHandler(this.btnClear_Click);
            // 
            // FrmSshChecker
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(379, 160);
            this.Controls.Add(this.btnClear);
            this.Controls.Add(this.lblCurrentRunning);
            this.Controls.Add(this.lblSshLoaded);
            this.Controls.Add(this.btnSaves);
            this.Controls.Add(this.lblRunningStatus);
            this.Controls.Add(this.prbRunningStatus);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.cboNumberTheards);
            this.Controls.Add(this.btnRun);
            this.Controls.Add(this.btnBrowseFile);
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle;
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.MaximizeBox = false;
            this.MinimizeBox = false;
            this.Name = "FrmSshChecker";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "SSH Checker";
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion
        private System.Windows.Forms.Button btnBrowseFile;
        private System.Windows.Forms.Button btnRun;
        private System.Windows.Forms.ComboBox cboNumberTheards;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.ProgressBar prbRunningStatus;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Label lblRunningStatus;
        private System.Windows.Forms.Button btnSaves;
        private System.Windows.Forms.Label lblSshLoaded;
        private System.Windows.Forms.Label lblCurrentRunning;
        private System.Windows.Forms.Button btnClear;
    }
}

