using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Football.Show.Entities.Enum;

namespace Football.Show.ViewModels
{
    public class Clip
    {
        public string Name { get; set; }
        public string Url { get; set; }
        public LinkType? LinkType { get; set; }
        public ClipType? ClipType { get; set; }
    }
}
