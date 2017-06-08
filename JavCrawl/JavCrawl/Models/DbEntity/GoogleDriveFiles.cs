using System;
using System.Collections.Generic;

namespace JavCrawl.Models.DbEntity
{
    public partial class GoogleDriveFiles
    {
        public int Id { get; set; }
        public int? AbleId { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? DeletedAt { get; set; }
        public string FileId { get; set; }
        public string FileName { get; set; }
        public int? FilmVideoId { get; set; }
        public string Status { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
