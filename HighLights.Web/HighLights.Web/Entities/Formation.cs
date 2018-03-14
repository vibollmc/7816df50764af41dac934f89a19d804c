using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace HighLights.Web.Entities
{
    public class Formation: Base
    {
        public int? MatchId { get; set; }
        public int? Number { get; set; }
        public string Name { get; set; }

        public Substitution Substitution { get; set; }
    }
}
