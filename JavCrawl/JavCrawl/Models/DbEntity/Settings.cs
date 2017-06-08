using System;
using System.Collections.Generic;

namespace JavCrawl.Models.DbEntity
{
    public partial class Settings
    {
        public int Id { get; set; }
        public DateTime? CreatedAt { get; set; }
        public string Data { get; set; }
        public string DataType { get; set; }
        public DateTime? DeletedAt { get; set; }
        public string Location { get; set; }
        public sbyte? Status { get; set; }
        public string Title { get; set; }
        public string Type { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
