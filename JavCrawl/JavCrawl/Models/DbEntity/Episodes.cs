using System;
using System.Collections.Generic;

namespace JavCrawl.Models.DbEntity
{
    public partial class Episodes
    {
        public int Id { get; set; }
        public DateTime? CreatedAt { get; set; }
        public int? CustomerId { get; set; }
        public DateTime? DeletedAt { get; set; }
        public string FileName { get; set; }
        public int? FilmId { get; set; }
        public int? FtpId { get; set; }
        public int? ServerId { get; set; }
        public string Slug { get; set; }
        public sbyte? Status { get; set; }
        public string SubEn { get; set; }
        public string SubVi { get; set; }
        public string Title { get; set; }
        public string Type { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public int? UserId { get; set; }
        public int? Viewed { get; set; }
    }
}
