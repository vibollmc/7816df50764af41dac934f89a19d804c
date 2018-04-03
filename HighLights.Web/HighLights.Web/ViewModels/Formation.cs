using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Entities.Enum;

namespace HighLights.Web.ViewModels
{
    public class Formation
    {
        public int? Number { get; set; }
        public string Name { get; set; }
        public FormationType Type { get; set; }
        public bool IsSubstitution { get; set; }

        public int? SubsNumber { get; set; }
        public string SubsName { get; set; }
        public int? SubsMinutes { get; set; }
    }
}
