using System;
using System.Collections.Generic;

namespace JavCrawl.Models.DbEntity
{
    public partial class Stars
    {
        public int Id { get; set; }
        public string Birth { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? DeletedAt { get; set; }
        public int? FtpId { get; set; }
        public string Fullname { get; set; }
        public string Height { get; set; }
        public string HomeTown { get; set; }
        public sbyte? Hot { get; set; }
        public string Seo { get; set; }
        public string Slug { get; set; }
        public string Story { get; set; }
        public string ThumbName { get; set; }
        public string Title { get; set; }
        public string TitleAscii { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
