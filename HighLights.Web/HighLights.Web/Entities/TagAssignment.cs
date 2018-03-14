using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace HighLights.Web.Entities
{
    public class TagAssignment
    {
        public int TagId { get; set; }
        public int MatchId { get; set; }

        public Tag Tag { get; set; }
        public Match Match { get; set; }
    }
}
