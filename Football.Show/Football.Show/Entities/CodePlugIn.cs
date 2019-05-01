using Football.Show.Entities.Enum;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.Entities
{
    public class CodePlugIn: Base
    {
        public string CodeText { get; set; }
        public CodeType CodeType { get; set; }
    }
}
