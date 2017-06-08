using System;
using System.Collections.Generic;

namespace JavCrawl.Models.DbEntity
{
    public partial class Tags
    {
        public int Id { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? DeletedAt { get; set; }
        public string Seo { get; set; }
        public string Slug { get; set; }
        public bool? Status { get; set; }
        public string Title { get; set; }
        public string TitleAscii { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
