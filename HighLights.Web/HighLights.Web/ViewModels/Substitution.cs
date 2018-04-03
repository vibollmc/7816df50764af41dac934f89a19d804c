using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Entities.Enum;

namespace HighLights.Web.ViewModels
{
    public class Substitution
    {
        public int? Number { get; set; }
        public string Name { get; set; }
        public FormationType Type { get; set; }
    }
}
