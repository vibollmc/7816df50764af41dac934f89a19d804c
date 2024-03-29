﻿using System;

namespace JavCrawl.Models.DbEntity
{
    public partial class Genres
    {
        public int Id { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? DeletedAt { get; set; }
        public string Description { get; set; }
        public sbyte? Menu { get; set; }
        public int? ParentId { get; set; }
        public string Seo { get; set; }
        public string Slug { get; set; }
        public sbyte? Status { get; set; }
        public string Title { get; set; }
        public string TitleAscii { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
