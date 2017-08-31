using System;

namespace JavCrawl.Models
{
    public class YoutubeVideo
    {
        public string VideoId { get; set; }
        public string ChannelId { get; set; }
        public string Title { get; set; }
        public DateTime? PublishedAt { get; set; }

        public DateTime? CommentedAt { get; set; }
        public string TokenNextPage { get; set; }
        public string TokenPrevPage { get; set; }
    }
}
