using System.Collections.Generic;

namespace JavCrawl.Models
{
    public class movies
    {
        public string id { get; set; }
        public string name { get; set; }
        public string image { get; set; }
        public string image_small { get; set; }
        public List<string> categories { get; set; }
        public List<string> tags { get; set; }
        public List<string> pornstars { get; set; }
        public string duration { get; set; }
        public string quality { get; set; }
        public string year { get; set; }
        public string view { get; set; }
        public string viewday { get; set; }
        public string viewweek { get; set; }
        public string viewmonth { get; set; }
        public string like { get; set; }
        public string likeday { get; set; }
        public string likeweek { get; set; }
        public string likemonth { get; set; }
        public string published { get; set; }
        public string dmca { get; set; }
        public string url { get; set; }
    }
}
