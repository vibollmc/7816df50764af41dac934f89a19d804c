using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models.DbEntity
{
    public class FilmStars
    {
        public int Id { get; set; }
        public int FilmId { get; set; }
        public int StarId { get; set; }
    }
}
