using System;
using System.Collections.Generic;

namespace JavCrawl.Models.DbEntity
{
    public partial class UserGroups
    {
        public int Id { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? DeletedAt { get; set; }
        public sbyte? Editble { get; set; }
        public string Title { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
