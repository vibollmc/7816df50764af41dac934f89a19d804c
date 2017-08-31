using JavCrawl.Dal.Context;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using JavCrawl.Models.DbEntity;

namespace JavCrawl.Dal.Implement
{
    public class BitPornoRepository : IBitPornoRepository
    {
        private readonly MySqlContext _dbContext;
        public BitPornoRepository(MySqlContext dbContext)
        {
            _dbContext = dbContext;
        }
        public Films GetFilmNeedRemote()
        {
            return _dbContext.Films.OrderByDescending(x => x.Order).FirstOrDefault(x => x.CustomerId == null);
        }

        public IList<Films> GetFilmsRemoting()
        {
            return _dbContext.Films.Where(x => x.CustomerId != null && x.CustomerId != -1 && x.CustomerId != 0).ToList();
        }

        public async Task<bool> UpdateRemoting(int filmId, int remoteId)
        {
            var film = _dbContext.Films.FirstOrDefault(x => x.Id == filmId);

            film.CustomerId = remoteId;
            await _dbContext.SaveChangesAsync();

            return true;
        }

        public async Task<bool> UpdateBitpornoEps(int filmId, string objectCode)
        {
            using(var dbTransaction = _dbContext.Database.BeginTransaction())
            {
                try
                {
                    var film = _dbContext.Films.FirstOrDefault(x => x.Id == filmId);

                    film.CustomerId = -1;

                    await _dbContext.SaveChangesAsync();

                    var eps = _dbContext.Episodes.FirstOrDefault(x => x.FilmId == filmId && x.FileName.Contains("bitporno"));

                    if (eps == null)
                    {
                        eps = new Episodes
                        {
                            FileName = string.Format("https://www.bitporno.com/embed/{0}", objectCode),
                            FilmId = filmId,
                            Title = "Server BP",
                            Type = "Full",
                            Viewed = 0,
                            CreatedAt = DateTime.Now
                        };

                        _dbContext.Episodes.Add(eps);
                    }
                    else
                    {
                        eps.FileName = string.Format("https://www.bitporno.com/embed/{0}", objectCode);
                    }

                    await _dbContext.SaveChangesAsync();

                    dbTransaction.Commit();
                }
                catch
                {
                    dbTransaction.Rollback();
                }
            }

            return true;
        }
    }
}
