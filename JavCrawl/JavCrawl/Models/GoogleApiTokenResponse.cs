namespace JavCrawl.Models
{
    public class GoogleApiTokenResponse
    {
        public string access_token { get; set; }
        public string token_type { get; set; }
        public string expires_in { get; set; }
        public string refresh_token { get; set; }
        public string Issued { get; set; }
        public string IssuedUtc { get; set; }
    }
}
