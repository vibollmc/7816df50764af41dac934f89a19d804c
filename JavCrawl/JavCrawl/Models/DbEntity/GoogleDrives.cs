using System;

namespace JavCrawl.Models.DbEntity
{
    public partial class GoogleDrives
    {
        public int Id { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? DeletedAt { get; set; }
        public string FolderId { get; set; }
        public string FolderName { get; set; }
        public string Status { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
