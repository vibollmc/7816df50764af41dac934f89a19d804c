using System;

namespace JavCrawl.Models.DbEntity
{
    public partial class Servers
    {
        public int Id { get; set; }
        public DateTime? CreatedAt { get; set; }
        public string Data { get; set; }
        public sbyte? Default { get; set; }
        public DateTime? DeletedAt { get; set; }
        public string Description { get; set; }
        public sbyte? Status { get; set; }
        public string Title { get; set; }
        public string TitleAscii { get; set; }
        public string Type { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
