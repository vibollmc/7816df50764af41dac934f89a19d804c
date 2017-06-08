using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models.DbEntity
{
    public class FilmCountries
    {
        public int Id { get; set; }
        public int FilmId { get; set; }
        public int CountryId { get; set; }
    }
}
