using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Entities;

namespace HighLights.Web.Dal.Context
{
    public interface IMatchRepository
    {
        Task<bool> CheckExsits(string slug);
        Task<bool> Save(Match match);
    }
}
