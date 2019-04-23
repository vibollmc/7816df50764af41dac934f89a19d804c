using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.ViewModels
{
    public class PagingResult
    {
        public int CurrentPage { get; set; }
        public int TotalPage { get; set; }
        public IEnumerable<Match> Matches { get; set; }
    }
}
