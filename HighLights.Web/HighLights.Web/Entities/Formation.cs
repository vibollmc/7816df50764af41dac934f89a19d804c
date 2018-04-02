using HighLights.Web.Entities.Enum;

namespace HighLights.Web.Entities
{
    public class Formation: Base
    {
        public int? MatchId { get; set; }
        public int? Number { get; set; }
        public string Name { get; set; }
        public FormationType Type { get; set; }
        public bool IsSubstitution { get; set; }

        public Substitution Substitution { get; set; }
    }
}
