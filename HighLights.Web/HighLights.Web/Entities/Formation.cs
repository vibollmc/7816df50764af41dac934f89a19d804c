using System.ComponentModel.DataAnnotations;
using HighLights.Web.Entities.Enum;

namespace HighLights.Web.Entities
{
    public class Formation: Base
    {
        public int? MatchId { get; set; }
        public int? Number { get; set; }
        [MaxLength(200)]
        public string Name { get; set; }
        public FormationType Type { get; set; }
        public bool IsSubstitution { get; set; }

        public virtual Substitution Substitution { get; set; }
    }
}
