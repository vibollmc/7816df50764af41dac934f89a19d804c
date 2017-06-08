using System;
using System.Collections.Generic;

namespace JavCrawl.Models.DbEntity
{
    public partial class Messages
    {
        public int Id { get; set; }
        public string Content { get; set; }
        public DateTime? CreatedAt { get; set; }
        public int? CustomerId { get; set; }
        public DateTime? DeletedAt { get; set; }
        public sbyte? Status { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
