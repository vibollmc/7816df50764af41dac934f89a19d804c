using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Entities.Enum;

namespace HighLights.Web.ViewModels
{
    public class Clip
    {
        public string Name { get; set; }
        public string Url { get; set; }
        public LinkType? LinkType { get; set; }
        public ClipType? ClipType { get; set; }
    }
}
