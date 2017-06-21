using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models
{
    public class YoutubeVideo
    {
        public string VideoId { get; set; }
        public string Title { get; set; }
        public DateTime? PublishedAt { get; set; }
    }
}
