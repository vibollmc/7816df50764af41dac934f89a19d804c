using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models.DbEntity
{
    public class Images
    {
        public int Id { get; set; }
        public int? IdArticle { get; set; }
        public int? ItemId { get; set; }
        public int? ServerId { get; set; }
        public string FileName { get; set; }
        public string Link { get; set; }
        public string Type { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public DateTime? DeletedAt { get; set; }
    }
}
