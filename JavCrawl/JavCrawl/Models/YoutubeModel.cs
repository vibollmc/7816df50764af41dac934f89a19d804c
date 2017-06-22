using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models
{
    public class YoutubeModel
    {
        public string Search { get; set; }
        public int? Max { get; set; }
        public int? Lat { get; set; }
        public int? Lon { get; set; }
        public string Radius { get; set; }
        public DateTime? PublishedAfter { get; set; }

        public IList<YoutubeVideo> Videos { get; set; }

        public IList<string> SelectedVideo { get; set; }
    }
}
