namespace JavCrawl.Models
{
    public class FtpAccount
    {
        public string host { get; set; }
        public string port { get; set; }
        public string username { get; set; }
        public string password { get; set; }
        public string dir { get; set; }
        public string public_url { get; set; }
    }
}
