using HighLights.Web.Entities.Enum;
using System.ComponentModel.DataAnnotations;

namespace HighLights.Web.Entities
{
    public class Clip : Base
    {
        public int? MatchId { get; set; }
        [Required, MaxLength(50)]
        public string Name { get; set; }
        [Required, MaxLength(1000)]
        public string Url { get; set; }
        public LinkType? LinkType { get; set; }
        public ClipType? ClipType { get; set; }

        public virtual Match Match { get; set; }
    }
}
