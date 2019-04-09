using System.ComponentModel.DataAnnotations;
using HighLights.Web.Entities.Enum;

namespace HighLights.Web.Entities
{
    public class Substitution : Base
    {
        public int? Number { get; set; }
        [MaxLength(200)]
        public string Name { get; set; }
        public int? Minutes { get; set; }
        public int? FormationId { get; set; }
        public int? MatchId { get; set; }
        public FormationType Type { get; set; }
        public virtual Formation Formation { get; set; }
        public virtual Match Match { get; set; }
    }
}
