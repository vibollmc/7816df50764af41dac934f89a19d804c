using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models.DbEntity
{
    public class YoutubeComment
    {
        public int? Id { get; set; }
        public string VideoId { get; set; }
        public string ChannelId { get; set; }
        public DateTime? CreatedAt { get; set; }
    }
}
