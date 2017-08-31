using JavCrawl.Models.DbEntity;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Dal.Context
{
    public interface IBitPornoRepository
    {
        IList<Films> GetFilmsRemoting();
        Films GetFilmNeedRemote();
        Task<bool> UpdateRemoting(int filmId, int remoteId);
        Task<bool> UpdateBitpornoEps(int filmId, string objectCode);
    }
}
