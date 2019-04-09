using System.ComponentModel.DataAnnotations;

namespace Football.Show.Entities
{
    public class CrawlLink : Base
    {
        [Required, MaxLength(500)]
        public string BaseLink { get; set; }
        public int? FromPage { get; set; }
        public int? ToPage { get; set; }
        public int? Finished { get; set; }
        public bool IsFinished { get; set; }
        public bool IsCircle { get; set; }
    }
}
