using System;

namespace JavCrawl.Models.DbEntity
{
    public partial class Feedbacks
    {
        public int Id { get; set; }
        public int? AbleId { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? DeletedAt { get; set; }
        public string Description { get; set; }
        public sbyte? Reported { get; set; }
        public string Status { get; set; }
        public string Title { get; set; }
        public string Type { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public string UserId { get; set; }
    }
}
