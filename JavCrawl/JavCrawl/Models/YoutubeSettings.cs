using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models
{
    public class YoutubeSettings
    {
        public string CommentDefault { get; set; }
        public string ApiKey { get; set; }
        public string ApplicationName { get; set; }
        public int MaxResultDefault { get; set; }
    }
}
