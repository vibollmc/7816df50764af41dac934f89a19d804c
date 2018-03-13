namespace SshChecker
{
    partial class SshChecker
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
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(SshChecker));
            this.btnBrowseFile = new System.Windows.Forms.Button();
            this.btnRun = new System.Windows.Forms.Button();
            this.prbRunningStatus = new System.Windows.Forms.ProgressBar();
            this.label2 = new System.Windows.Forms.Label();
            this.lblChecked = new System.Windows.Forms.Label();
            this.btnSaves = new System.Windows.Forms.Button();
            this.lblSshLoaded = new System.Windows.Forms.Label();
            this.lblChecking = new System.Windows.Forms.Label();
            this.btnClear = new System.Windows.Forms.Button();
            this.button1 = new System.Windows.Forms.Button();
            this.label3 = new System.Windows.Forms.Label();
            this.numericUpDown1 = new System.Windows.Forms.NumericUpDown();
            this.prbChecked = new System.Windows.Forms.ProgressBar();
            this.lblIpRunning = new System.Windows.Forms.Label();
            ((System.ComponentModel.ISupportInitialize)(this.numericUpDown1)).BeginInit();
            this.SuspendLayout();
            // 
            // btnBrowseFile
            // 
            this.btnBrowseFile.Location = new System.Drawing.Point(321, 23);
            this.btnBrowseFile.Name = "btnBrowseFile";
            this.btnBrowseFile.Size = new System.Drawing.Size(121, 23);
            this.btnBrowseFile.TabIndex = 2;
            this.btnBrowseFile.Text = "Select SSH File";
            this.btnBrowseFile.UseVisualStyleBackColor = true;
            this.btnBrowseFile.Click += new System.EventHandler(this.button1_Click);
            // 
            // btnRun
            // 
            this.btnRun.ForeColor = System.Drawing.Color.Firebrick;
            this.btnRun.Location = new System.Drawing.Point(190, 82);
            this.btnRun.Name = "btnRun";
            this.btnRun.Size = new System.Drawing.Size(91, 23);
            this.btnRun.TabIndex = 5;
            this.btnRun.Text = "Run Check";
            this.btnRun.UseVisualStyleBackColor = true;
            this.btnRun.Click += new System.EventHandler(this.button2_Click);
            // 
            // prbRunningStatus
            // 
            this.prbRunningStatus.Dock = System.Windows.Forms.DockStyle.Bottom;
            this.prbRunningStatus.Location = new System.Drawing.Point(0, 168);
            this.prbRunningStatus.Name = "prbRunningStatus";
            this.prbRunningStatus.Size = new System.Drawing.Size(459, 10);
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
            // lblChecked
            // 
            this.lblChecked.BackColor = System.Drawing.Color.Transparent;
            this.lblChecked.Dock = System.Windows.Forms.DockStyle.Bottom;
            this.lblChecked.Location = new System.Drawing.Point(0, 140);
            this.lblChecked.Name = "lblChecked";
            this.lblChecked.Size = new System.Drawing.Size(459, 18);
            this.lblChecked.TabIndex = 7;
            this.lblChecked.Text = "label3";
            this.lblChecked.TextAlign = System.Drawing.ContentAlignment.MiddleLeft;
            // 
            // btnSaves
            // 
            this.btnSaves.Location = new System.Drawing.Point(321, 84);
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
            // lblChecking
            // 
            this.lblChecking.Dock = System.Windows.Forms.DockStyle.Bottom;
            this.lblChecking.Location = new System.Drawing.Point(0, 127);
            this.lblChecking.Name = "lblChecking";
            this.lblChecking.Size = new System.Drawing.Size(459, 13);
            this.lblChecking.TabIndex = 9;
            this.lblChecking.Text = "label3";
            // 
            // btnClear
            // 
            this.btnClear.Location = new System.Drawing.Point(321, 52);
            this.btnClear.Name = "btnClear";
            this.btnClear.Size = new System.Drawing.Size(121, 23);
            this.btnClear.TabIndex = 10;
            this.btnClear.Text = "Clear Queue";
            this.btnClear.UseVisualStyleBackColor = true;
            this.btnClear.Click += new System.EventHandler(this.btnClear_Click);
            // 
            // button1
            // 
            this.button1.ForeColor = System.Drawing.Color.Blue;
            this.button1.Location = new System.Drawing.Point(12, 82);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(91, 23);
            this.button1.TabIndex = 11;
            this.button1.Text = "Tools";
            this.button1.UseVisualStyleBackColor = true;
            this.button1.Click += new System.EventHandler(this.button1_Click_1);
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Location = new System.Drawing.Point(53, 52);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(48, 13);
            this.label3.TabIndex = 12;
            this.label3.Text = "Timeout:";
            // 
            // numericUpDown1
            // 
            this.numericUpDown1.Location = new System.Drawing.Point(107, 50);
            this.numericUpDown1.Name = "numericUpDown1";
            this.numericUpDown1.Size = new System.Drawing.Size(43, 20);
            this.numericUpDown1.TabIndex = 13;
            this.numericUpDown1.Value = new decimal(new int[] {
            20,
            0,
            0,
            0});
            // 
            // prbChecked
            // 
            this.prbChecked.Dock = System.Windows.Forms.DockStyle.Bottom;
            this.prbChecked.ForeColor = System.Drawing.Color.Firebrick;
            this.prbChecked.Location = new System.Drawing.Point(0, 158);
            this.prbChecked.Name = "prbChecked";
            this.prbChecked.Size = new System.Drawing.Size(459, 10);
            this.prbChecked.TabIndex = 14;
            // 
            // lblIpRunning
            // 
            this.lblIpRunning.Dock = System.Windows.Forms.DockStyle.Bottom;
            this.lblIpRunning.Location = new System.Drawing.Point(0, 114);
            this.lblIpRunning.Name = "lblIpRunning";
            this.lblIpRunning.Size = new System.Drawing.Size(459, 13);
            this.lblIpRunning.TabIndex = 15;
            this.lblIpRunning.Text = "label4";
            // 
            // SshChecker
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(459, 178);
            this.Controls.Add(this.lblIpRunning);
            this.Controls.Add(this.numericUpDown1);
            this.Controls.Add(this.label3);
            this.Controls.Add(this.button1);
            this.Controls.Add(this.btnClear);
            this.Controls.Add(this.lblChecking);
            this.Controls.Add(this.lblSshLoaded);
            this.Controls.Add(this.btnSaves);
            this.Controls.Add(this.lblChecked);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.btnRun);
            this.Controls.Add(this.btnBrowseFile);
            this.Controls.Add(this.prbChecked);
            this.Controls.Add(this.prbRunningStatus);
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle;
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.MaximizeBox = false;
            this.MinimizeBox = false;
            this.Name = "SshChecker";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "SSH Checker";
            this.FormClosing += new System.Windows.Forms.FormClosingEventHandler(this.SshChecker_FormClosing);
            ((System.ComponentModel.ISupportInitialize)(this.numericUpDown1)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion
        private System.Windows.Forms.Button btnBrowseFile;
        private System.Windows.Forms.Button btnRun;
        private System.Windows.Forms.ProgressBar prbRunningStatus;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Label lblChecked;
        private System.Windows.Forms.Button btnSaves;
        private System.Windows.Forms.Label lblSshLoaded;
        private System.Windows.Forms.Label lblChecking;
        private System.Windows.Forms.Button btnClear;
        private System.Windows.Forms.Button button1;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.NumericUpDown numericUpDown1;
        private System.Windows.Forms.ProgressBar prbChecked;
        private System.Windows.Forms.Label lblIpRunning;
    }
}

