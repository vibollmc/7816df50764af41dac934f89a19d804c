namespace HighLights.Web.Entities
{
    public class CrawlLink : Base
    {
        public string BaseLink { get; set; }
        public int? FromPage { get; set; }
        public int? ToPage { get; set; }
        public int? Finished { get; set; }
        public bool IsFinished { get; set; }
        public bool IsCircle { get; set; }
    }
}
