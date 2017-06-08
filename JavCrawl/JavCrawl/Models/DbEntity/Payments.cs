using System;
using System.Collections.Generic;

namespace JavCrawl.Models.DbEntity
{
    public partial class Payments
    {
        public int Id { get; set; }
        public DateTime? CreatedAt { get; set; }
        public int? CustomerId { get; set; }
        public string Data { get; set; }
        public DateTime? DeletedAt { get; set; }
        public decimal? Price { get; set; }
        public string Status { get; set; }
        public int? TimePay { get; set; }
        public int? TimePending { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public int? Viewed { get; set; }
    }
}
