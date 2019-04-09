using System.Threading.Tasks;
using Football.Show.Entities;

namespace Football.Show.Dal.Context
{
    public interface IImageServerRepository
    {
        Task<ImageServer> GetActiveImageServer();
    }
}
