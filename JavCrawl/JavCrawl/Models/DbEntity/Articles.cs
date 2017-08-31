using System;

namespace JavCrawl.Models.DbEntity
{
    public partial class Articles
    {
        public int Id { get; set; }
        public string Content { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? DeletedAt { get; set; }
        public string Description { get; set; }
        public sbyte? Online { get; set; }
        public string Seo { get; set; }
        public string Slug { get; set; }
        public bool? Status { get; set; }
        public string Title { get; set; }
        public string TitleAscii { get; set; }
        public string Type { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public int? Viewed { get; set; }
    }
}
