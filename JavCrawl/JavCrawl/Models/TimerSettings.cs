namespace JavCrawl.Models
{
    public class TimerSettings
    {
        public int Interval { get; set; }
        public bool Enabled { get; set; }
        public bool EnabledRemoteUpload { get; set; }
        public bool EnabledCrawler { get; set; }
        public bool EnabledReloadEpisodesLink { get; set; }
        public bool EnabledGenarateSlideAndFilmMember { get; set; }
    }
}
