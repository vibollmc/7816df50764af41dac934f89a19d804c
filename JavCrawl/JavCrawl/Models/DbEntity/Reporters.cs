using System;
using System.Collections.Generic;

namespace JavCrawl.Models.DbEntity
{
    public partial class Reporters
    {
        public int Id { get; set; }
        public int? AbleId { get; set; }
        public string Content { get; set; }
        public DateTime? CreatedAt { get; set; }
        public int? CustomerId { get; set; }
        public DateTime? DeletedAt { get; set; }
        public int? ErrorId { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
