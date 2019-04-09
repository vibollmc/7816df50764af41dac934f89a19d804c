using System.Threading.Tasks;
using Football.Show.Entities;

namespace Football.Show.Dal.Context
{
    public interface ICrawlLinkRepository
    {
        Task<CrawlLink> GetActive();
        Task<bool> UpdateFinished(int? id);
    }
}
