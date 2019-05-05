using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Football.Show.Entities.Enum;

namespace Football.Show.ViewModels
{
    public class Substitution
    {
        public int? Number { get; set; }
        public string Name { get; set; }
        public FormationType Type { get; set; }

        public bool IsSubstitution { get; set; }
        public int? SubsMinutes { get; set; }
        public int SubsYellowCard { get; set; }
        public int SubsRedCard { get; set; }
        public int SubsScores { get; set; }
    }
}
