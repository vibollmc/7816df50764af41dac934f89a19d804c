using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Entities.Enum;

namespace HighLights.Web.Utilities.Model
{
    public class ActionSubstitution
    {
        public string In { get; set; }
        public string Min { get; set; }
        public string Out { get; set; }
        public FormationType Type { get; set; }
    }
}
