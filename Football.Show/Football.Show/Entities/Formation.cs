using System.ComponentModel.DataAnnotations;
using Football.Show.Entities.Enum;

namespace Football.Show.Entities
{
    public class Formation: Base
    {
        public int? MatchId { get; set; }
        public int? Number { get; set; }
        [MaxLength(200)]
        public string Name { get; set; }
        public FormationType Type { get; set; }
        public bool IsSubstitution { get; set; }
        public int YellowCard { get; set; }
        public int RedCard { get; set; }
        public int Scores { get; set; }

        public virtual Substitution Substitution { get; set; }
    }
}
