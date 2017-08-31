namespace JavCrawl.Models
{
    public class FtpResults
    {
        public bool IsSuccessful { get; set; }
        public string FileName { get; set; }
        public string FullPath { get; set; }
        public int ServerId { get; set; }
    }
}
