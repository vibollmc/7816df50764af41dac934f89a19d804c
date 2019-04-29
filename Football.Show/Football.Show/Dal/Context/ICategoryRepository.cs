using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Football.Show.Entities;

namespace Football.Show.Dal.Context
{
    public interface ICategoryRepository
    {
        Task<IEnumerable<Category>> GetCategories();
        Task<IEnumerable<Category>> GetMenuCategories();
        Task<Category> GetCategory(string slug);
    }
}
