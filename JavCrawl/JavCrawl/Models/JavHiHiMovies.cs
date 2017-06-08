using System.Collections.Generic;

namespace JavCrawl.Models
{
    public class JavHiHiMovies
    {
        public IList<JavHiHiMovie> movies{ get; set; }
        public string next_page_url { get; set; }
        public string total { get; set; }
        public string page { get; set; }
        public string total_page { get; set; }
    }
    public class JavHiHiMovie
    {
        public string id { get; set; }
        public string name { get; set; }
        public string image { get; set; }
        public string image_small { get; set; }
        public IEnumerable<string> categories { get; set; }
        public IEnumerable<string> tags { get; set; }
        public IEnumerable<string> pornstars { get; set; }
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

        public string descriptions { get; set; }
        public IList<string> linkepisode { get; set; }
    }
}
